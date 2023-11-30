import Foundation

public struct Input {
    private let handle: FileHandle
    
    public init(url: URL) throws {
        self.handle = try FileHandle(forReadingFrom: url)
    }
}
