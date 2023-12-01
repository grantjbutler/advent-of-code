extension Sequence {
    public func dump(name: String? = nil) -> Self {
        return Swift.dump(self, name: name)
    }
    
    public func inspect(_ block: (Self) -> Void) -> Self {
        block(self)
        
        return self
    }
}

extension Sequence where Element: BinaryInteger {
    public func sum() -> Element {
        reduce(Element.zero, (+))
    }
}

// MARK: -

public enum SortDirection {
    case ascending
    case descending
}

extension Sequence where Element: Comparable {
    public func sorted(in direction: SortDirection) -> [Element] {
        let operation: (Element, Element) -> Bool
        switch direction {
        case .ascending: operation = (<)
        case .descending: operation = (>)
        }
        
        return sorted(by: operation)
    }
}

// MARK: -

extension Sequence where Element: Sequence, Element.Element: Hashable {
    public func commonElements() -> any Sequence<Element.Element> {
        var iterator = makeIterator()
        guard let first = iterator.next() else { return [] }
    
        return dropFirst().reduce(into: Set<Element.Element>(first)) { partialResult, sequence in
            partialResult.formIntersection(Set(sequence))
        }
    }
}

// MARK: -

extension Sequence where Element == String {
    public var asIntegers: [Int] {
        compactMap(Int.init(_:))
    }
}
