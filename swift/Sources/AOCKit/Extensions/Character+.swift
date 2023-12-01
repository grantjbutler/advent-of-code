import Foundation

extension Character {
    public var indexInAlphabet: Int {
        assert(isASCII)
        
        return Int(self.lowercased().first!.asciiValue! - Character("a").asciiValue!)
    }
}
