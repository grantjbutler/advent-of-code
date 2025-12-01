import Parsing

public struct IntegersParser: Parser {
    public init() { }
    
    public var body: some Parser<Substring, [Int]> {
        Many {
            Digits()
        } separator: {
            Whitespace()
        }
    }
}
