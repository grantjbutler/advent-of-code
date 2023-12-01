import Algorithms
import Foundation

public struct Input {
    public let buffer: Substring
    
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
        Lines(buffer.split(separator: "\n").map(Input.init(_:)))
    }
    
    public var uniqueCharacters: some Sequence<Character> {
        return buffer.uniqued()
    }
    
    public var integer: Int? { Int(buffer) }
    public var isEmpty: Bool { buffer.isEmpty }
    
    public func evenlyChunked(in count: Int) -> [Input] {
        return buffer.evenlyChunked(in: count).map(Input.init(_:))
    }
    
    public func split(separator: String) -> [Input] {
        buffer.split(separator: separator).map(Input.init(_:))
    }
}

public struct Lines {
    private let lines: [Input]
    
    init(_ lines: [Input]) {
        self.lines = lines
    }
    
    public var integers: [Int] {
        let integers = lines.compactMap(\.integer)
        assert(integers.count == lines.count)
        return integers
    }
}

extension Lines: Sequence {
    public typealias Element = Input
    public typealias Iterator = IndexingIterator<[Input]>
    
    public func makeIterator() -> IndexingIterator<[Input]> {
        lines.makeIterator()
    }
}

extension Lines: Collection {
    public func index(after i: Array<Input>.Index) -> Array<Input>.Index {
        lines.index(after: i)
    }
    
    public subscript(position: Array<Input>.Index) -> Input {
        _read {
            yield lines[position]
        }
    }
    
    public var startIndex: Array<Input>.Index {
        lines.startIndex
    }
    
    public var endIndex: Array<Input>.Index {
        lines.endIndex
    }
    
    public typealias Index = Array<Input>.Index
}
