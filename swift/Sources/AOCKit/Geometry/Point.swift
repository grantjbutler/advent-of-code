public struct Point: Hashable, Sendable {
    public let x: Int
    public let y: Int
    
    public init(x: Int, y: Int) {
        self.x = x
        self.y = y
    }
    
    public static var zero: Point {
        .init(x: 0, y: 0)
    }
    
    public func point(offsetBy offset: Int, inDirection direction: Direction) -> Point {
        switch direction {
        case .north:
            return Point(x: x, y: y - offset)
        case .south:
            return Point(x: x, y: y + offset)
        case .west:
            return Point(x: x - offset, y: y)
        case .east:
            return Point(x: x + offset, y: y)
        }
    }
}
