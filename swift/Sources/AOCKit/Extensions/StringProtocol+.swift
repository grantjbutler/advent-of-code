extension StringProtocol {
    public var lines: [String] {
        return components(separatedBy: .newlines)
    }
    
    public var integer: Int? { Int(self) }
    
    public var digits: [Character] { filter(\.isNumber).map { $0 } }
    
    public func indexes(of character: Character) -> Set<Int> {
        return zip(self, 0...).reduce(into: Set<Int>()) { indexes, pair in
            guard pair.0 == character else { return }
            indexes.insert(pair.1)
        }
    }
}
