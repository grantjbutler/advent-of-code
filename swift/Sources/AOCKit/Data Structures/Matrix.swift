public struct Matrix<Element> {
    private var collection: [Element]
    private let rowLength: Int
    
    public init(_ collection: [[Element]]) {
        assert(!collection.isEmpty && collection.allSatisfy { $0.count == collection.first?.count })
        
        self.collection = collection.flatMap(\.self)
        self.rowLength = collection[0].count
    }
    
//    public init(_ collection: [some Sequence<Element>]) {
//        //                       v warning: Capture of non-Sendable type '(some Sequence<Element>).Type' in an isolated closure
//        self.init(collection.map(Array.init))
//    }
    
    private func index(from point: Point) -> Int {
        rowLength * point.y + point.x
    }
    
    private func point(from index: Int) -> Point {
        let (y, x) = index.quotientAndRemainder(dividingBy: rowLength)
        return Point(x: x, y: y)
    }
    
    public func get(at point: Point) -> Element {
        return collection[index(from: point)]
    }
    
    public mutating func set(_ value: Element, at point: Point) {
        let index = index(from: point)
        assert(collection.indices.contains(index))
        
        collection[index] = value
    }
    
    public func contains(point: Point) -> Bool {
        let index = index(from: point)
        return collection.indices.contains(index)
    }
}

extension Matrix where Element: Equatable {
    public func firstPoint(of element: Element) -> Point? {
        guard let index = collection.firstIndex(of: element) else { return nil }
        return point(from: index)
    }
}

extension Matrix: Sendable where Element: Sendable {}

extension Matrix: Sequence {
    public func makeIterator() -> Array<Element>.Iterator {
        return collection.makeIterator()
    }
}

extension Matrix: Collection {
    public typealias Index = Array<Element>.Index
    
    public var startIndex: Array<Element>.Index {
        collection.startIndex
    }
    
    public var endIndex: Array<Element>.Index {
        collection.endIndex
    }
    
    public subscript(position: Array<Element>.Index) -> Element {
        _read {
            yield collection[position]
        }
    }
    
    public func index(after i: Array<Element>.Index) -> Array<Element>.Index {
        return collection.index(after: i)
    }
}

extension Matrix: BidirectionalCollection {
    public func index(before i: Index) -> Index {
        collection.index(before: i)
    }
}

//extension Matrix: RandomAccessCollection {}
