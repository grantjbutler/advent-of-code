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
