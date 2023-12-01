extension StringProtocol {
    public var lines: [String] {
        return components(separatedBy: .newlines)
    }
    
    public var integer: Int? { Int(self) }
    
    public var digits: [Character] { filter(\.isNumber).map { $0 } }
}
