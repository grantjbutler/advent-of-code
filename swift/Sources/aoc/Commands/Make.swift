import AOCKit
import ArgumentParser
import Foundation
import SwiftParser
import SwiftSyntax

struct Make: AsyncParsableCommand {
    static var configuration = CommandConfiguration(
        abstract: "Creates the basic structure for a day's problem."
    )

    @Argument
    var day: Day = .today
    
    func run() async throws {
        let folderURL = AOC.rootDir
            .appending(component: "AOC\(day.year)", directoryHint: .isDirectory)
        
        let fileURL = folderURL.appending(component: "Day\(day.day).swift", directoryHint: .notDirectory)
        
        if FileManager.default.fileExists(atPath: fileURL.path(percentEncoded: false)) {
            print("File already exists for day \(day.day). Skipping creation.")
            return
        }
        
        try? FileManager.default.createDirectory(at: folderURL, withIntermediateDirectories: true)
        
        try template(day).write(to: fileURL, atomically: true, encoding: .utf8)
        
        try updateRegistrySourceCode()
        
        print("Successfully created file for day \(day.day).")
    }
    
    func updateRegistrySourceCode() throws {
        let registryURL = AOC.rootDir
            .appending(components: "AOCSolutions", "Registry.swift", directoryHint: .notDirectory)
        
        var registrySoureCode = try String(contentsOf: registryURL)
        
        let sourceFile = Parser.parse(source: registrySoureCode)
        let statements = sourceFile
            .statements
            .map { statement in
                guard case let .decl(decl) = statement.item,
                    let `enum` = decl.as(EnumDeclSyntax.self) else { return statement }
                    
                guard `enum`.name.text == "Registry" else { return statement }
                    
                return statement
                    .with(\.item, .decl(DeclSyntax(modify(enum: `enum`))))
            }
        
        let modifiedSourceFile = sourceFile
            .with(\.statements, CodeBlockItemListSyntax(statements))
        
        registrySoureCode = ""
        modifiedSourceFile.write(to: &registrySoureCode)
        
        try registrySoureCode.write(to: registryURL, atomically: true, encoding: .utf8)
    }
    
    func modify(`enum`: EnumDeclSyntax) -> EnumDeclSyntax {
        let members = `enum`.memberBlock.members.map { member in
            guard let variable = member.decl.as(VariableDeclSyntax.self) else { return member }
            
            let bindings = variable.bindings.map { binding in
                guard let identifierPattern = binding.pattern.as(IdentifierPatternSyntax.self) else { return binding }
                guard identifierPattern.identifier.text == "solutions" else { return binding }
                
                guard let initializer = binding.initializer else { return binding }
                guard let dictionaryExpression = initializer.value.as(DictionaryExprSyntax.self) else { return binding }
                
                let content: DictionaryExprSyntax.Content
                switch dictionaryExpression.content {
                case .colon:
                    content = .elements(DictionaryElementListSyntax([makeRegistryEntry()]))
                case var .elements(elementList):
                    elementList.append(makeRegistryEntry())
                    
                    content = .elements(elementList)
                }
                
                return binding.with(
                    \.initializer, binding.initializer?.with(
                        \.value, ExprSyntax(DictionaryExprSyntax(content: content))
                    )
                )
            }
            
            return member
                .with(\.decl, DeclSyntax(
                    variable
                        .with(\.bindings, PatternBindingListSyntax(bindings))
                ))
        }
        
        return `enum`
            .with(\.memberBlock, `enum`.memberBlock
                .with(\.members, MemberBlockItemListSyntax(members))
            )
    }
    
    private func makeRegistryEntry() -> DictionaryElementSyntax {
        return .init(
            leadingTrivia: [.newlines(1), .spaces(8)],
            key: FunctionCallExprSyntax(
                calledExpression: MemberAccessExprSyntax(
                    period: .periodToken(),
                    name: .keyword(.`init`)
                ),
                leftParen: .leftParenToken(),
                arguments: LabeledExprListSyntax([
                    LabeledExprSyntax(
                        label: .identifier("year"),
                        colon: .colonToken(),
                        expression: IntegerLiteralExprSyntax(leadingTrivia: .space, literal: .integerLiteral("\(day.year)")),
                        trailingComma: .commaToken()
                    ),
                    LabeledExprSyntax(
                        leadingTrivia: .space,
                        label: .identifier("day"),
                        colon: .colonToken(),
                        expression: IntegerLiteralExprSyntax(leadingTrivia: .space, literal: .integerLiteral("\(day.day)"))
                    ),
                ]),
                rightParen: .rightParenToken()
            ),
            colon: .colonToken(),
            value: FunctionCallExprSyntax(
                leadingTrivia: .space,
                calledExpression: MemberAccessExprSyntax(
                    period: .periodToken(),
                    name: .keyword(.`init`)
                ),
                leftParen: .leftParenToken(),
                arguments: LabeledExprListSyntax([
                    LabeledExprSyntax(
                        expression: MemberAccessExprSyntax(
                            base: MemberAccessExprSyntax(
                                base: DeclReferenceExprSyntax(baseName: .identifier("AOC\(day.year)")),
                                period: .periodToken(),
                                declName: DeclReferenceExprSyntax(baseName: .identifier("Day\(day.day)"))
                            ),
                            period: .periodToken(),
                            declName: DeclReferenceExprSyntax(baseName: .keyword(.`self`))
                        )
                    ),
                ]),
                rightParen: .rightParenToken()
            ),
            trailingComma: .commaToken(),
            trailingTrivia: [.newlines(1), .spaces(4)]
        )
    }
}

private func template(_ day: Day) -> String {
    return """
    import AOCKit

    public enum Day\(day.day): Solution {
        public typealias SolutionInput = String
    
        public static func part1(_ input: SolutionInput) -> some CustomStringConvertible {
            return ""
        }
        
        public static func part2(_ input: SolutionInput) -> some CustomStringConvertible {
            return ""
        }
    }
    """
}
