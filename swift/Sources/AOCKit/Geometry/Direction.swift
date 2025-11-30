public enum Direction {
    case north
    case south
    case east
    case west
    
    public var rotatedRight: Direction {
        switch self {
        case .north:
            .east
        case .south:
            .west
        case .east:
            .south
        case .west:
            .north
        }
    }
}
