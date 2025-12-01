// swift-tools-version: 6.2

import PackageDescription
import Foundation

let package = Package(
    name: "aoc",
    platforms: [
        .macOS(.v15)
    ],
    dependencies: [
        .package(url: "https://github.com/apple/swift-argument-parser", from: "1.6.0"),
        .package(url: "https://github.com/apple/swift-algorithms", from: "1.2.0"),
        .package(url: "https://github.com/apple/swift-collections", from: "1.3.0"),
        .package(url: "https://github.com/swiftlang/swift-syntax", from: "602.0.0"),
        .package(url: "https://github.com/pointfreeco/swift-parsing", from: "0.14.0"),
    ],
    targets: [
        .executableTarget(
            name: "aoc",
            dependencies: [
                "AOCKit",
                "AOCSolutions",
                .product(name: "ArgumentParser", package: "swift-argument-parser"),
                .product(name: "SwiftSyntax", package: "swift-syntax"),
                .product(name: "SwiftParser", package: "swift-syntax"),
            ]),
        .target(name: "AOCKit", dependencies: [
            .product(name: "Algorithms", package: "swift-algorithms"),
            .product(name: "Collections", package: "swift-collections"),
            .product(name: "Parsing", package: "swift-parsing"),
        ]),
        .target(name: "AOCSolutions", dependencies: ["AOCKit", "AOC2022", "AOC2023", "AOC2024", "AOC2025"]),
        .target(name: "AOC2022", dependencies: ["AOCKit"], resources: [.copy("Inputs")]),
        .target(name: "AOC2023", dependencies: ["AOCKit"], resources: [.copy("Inputs")]),
        .target(name: "AOC2024", dependencies: ["AOCKit"], resources: [.copy("Inputs")]),
        .target(name: "AOC2025", dependencies: ["AOCKit"], resources: [.copy("Inputs")]),
    ]
)
