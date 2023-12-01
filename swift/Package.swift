// swift-tools-version: 5.8
// The swift-tools-version declares the minimum version of Swift required to build this package.

import PackageDescription
import Foundation

let package = Package(
    name: "aoc",
    platforms: [
        .macOS(.v13)
    ],
    dependencies: [
        // Dependencies declare other packages that this package depends on.
        // .package(url: /* package url */, from: "1.0.0"),
        .package(url: "https://github.com/apple/swift-argument-parser", from: "1.2.0"),
        .package(url: "https://github.com/apple/swift-algorithms", from: "1.2.0"),
        .package(url: "https://github.com/apple/swift-collections", from: "1.0.5"),
    ],
    targets: [
        // Targets are the basic building blocks of a package. A target can define a module or a test suite.
        // Targets can depend on other targets in this package, and on products in packages this package depends on.
        .executableTarget(
            name: "aoc",
            dependencies: [
                "AOCKit",
                "AOCSolutions",
                .product(name: "ArgumentParser", package: "swift-argument-parser")
            ]),
        .target(name: "AOCKit", dependencies: [
            .product(name: "Algorithms", package: "swift-algorithms"),
            .product(name: "Collections", package: "swift-collections"),
        ]),
        .target(name: "AOCSolutions", dependencies: ["AOCKit", "AOC2022", "AOC2023"]),
        .target(name: "AOC2022", dependencies: ["AOCKit"], resources: [.copy("Inputs")]),
        .target(name: "AOC2023", dependencies: ["AOCKit"], resources: [.copy("Inputs")])
    ]
)
