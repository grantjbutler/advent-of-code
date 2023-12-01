import Algorithms
import Foundation

public struct Input {
    public private(set) var buffer: Substring
    
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
    public var digits: String { buffer.filter(\.isNumber) }
    public var isEmpty: Bool { buffer.isEmpty }
    
    public func evenlyChunked(in count: Int) -> [Input] {
        return buffer.evenlyChunked(in: count).map(Input.init(_:))
    }
    
    public func split(separator: String) -> [Input] {
        buffer.split(separator: separator).map(Input.init(_:))
    }
}

extension Input: Sequence {
    public typealias Element = Substring.Element
    public typealias Iterator = Substring.Iterator
    
    public func makeIterator() -> Substring.Iterator {
        return buffer.makeIterator()
    }
}

extension Input: Collection {
    public func index(after i: Substring.Index) -> Substring.Index {
        buffer.index(after: i)
    }
    
    public subscript(position: Substring.Index) -> Substring.Element {
        _read {
            yield buffer[position]
        }
    }
    
    public subscript(bounds: Range<Self.Index>) -> Self.SubSequence {
        get {
            buffer[bounds]
        }
    }
    
    public var startIndex: Substring.Index {
        buffer.startIndex
    }
    
    public var endIndex: Substring.Index {
        buffer.endIndex
    }
    
    public typealias Index = Substring.Index
}

extension Input: BidirectionalCollection {
    public func index(before i: Substring.Index) -> Substring.Index {
        buffer.index(before: i)
    }
}

extension Input: RangeReplaceableCollection {
    public typealias SubSequence = Substring.SubSequence

    public init() {
        self.init("")
    }
    
    public mutating func replaceSubrange<C>(_ subrange: Range<Self.Index>, with newElements: C) where C : Collection, Self.Element == C.Element {
        buffer.replaceSubrange(subrange, with: newElements)
    }
}
