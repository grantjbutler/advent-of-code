import Foundation

public struct Input {
    private let buffer: Substring
    
    public init(url: URL) throws {
        self.buffer = try String(contentsOf: url)[...]
    }
    
    public init(_ string: String) {
        self.buffer = string[...]
    }
    
    public init(_ substring: Substring) {
        self.buffer = substring
    }
    
    public var lines: Lines {
        Lines(buffer.split(separator: "\n").map(Line.init(_:)))
    }
    
    public var integer: Int? {
        Int(buffer)
    }
    
    public func split(separator: String) -> any Collection<Input> {
        buffer.split(separator: separator).map(Input.init(_:))
    }
}

public struct Line {
    private let buffer: Substring
    
    public init(_ substring: Substring) {
        self.buffer = substring
    }
    
    public init(_ string: String) {
        self.buffer = string[...]
    }

    public var integer: Int? { Int(buffer) }
    public var isEmpty: Bool { buffer.isEmpty }
    
    public func split(separator: String) -> any Collection<Input> {
        buffer.split(separator: separator).map(Input.init(_:))
    }
}

public struct Lines {
    private let lines: [Line]
    
    init(_ lines: [Line]) {
        self.lines = lines
    }
    
    public var integers: [Int] {
        let integers = lines.compactMap(\.integer)
        assert(integers.count == lines.count)
        return integers
    }
}

extension Lines: Sequence {
    public typealias Element = Line
    public typealias Iterator = IndexingIterator<[Line]>
    
    public func makeIterator() -> IndexingIterator<[Line]> {
        lines.makeIterator()
    }
}
