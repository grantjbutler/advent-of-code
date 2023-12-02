import Parsing

extension Sequence {
    public func dump(name: String? = nil) -> Self {
        return Swift.dump(self, name: name)
    }
    
    public func inspect(_ block: (Self) -> Void) -> Self {
        block(self)
        
        return self
    }
}

// MARK: -

extension Sequence {
    public func sum<Number: AdditiveArithmetic>(_ getter: (Element) -> Number) -> Number {
        reduce(into: .zero) { partial, element in
            partial += getter(element)
        }
    }
}

extension Sequence {
    public func product<Number: Numeric>(_ getter: (Element) -> Number) -> Number {
        reduce(into: 1) { partial, element in
            partial *= getter(element)
        }
    }
}

extension Sequence where Element: AdditiveArithmetic {
    public func sum() -> Element {
        reduce(Element.zero, (+))
    }
}
 
extension Sequence where Element: Numeric {
    public func product() -> Element {
        reduce(1, (*))
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

extension Collection where Element == String {
    public var asIntegers: [Int] {
        let integers = compactMap(Int.init(_:))
        assert(integers.count == count)
        return integers
    }
}

// MARK: - 

extension Sequence {
    public func parse<P: Parser>(using parser: P) throws -> [P.Output] where P.Input == Element {
        try map { try parser.parse($0) }
    }
}

extension Sequence where Element == String {
    public func parse<P: Parser>(using parser: P) throws -> [P.Output] where P.Input == Substring {
        try map { try parser.parse($0[...]) }
    }
}
