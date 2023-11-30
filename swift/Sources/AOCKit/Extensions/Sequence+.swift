extension Sequence where Element: BinaryInteger {
    public func sum() -> Element {
        reduce(Element.zero, (+))
    }
}

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
