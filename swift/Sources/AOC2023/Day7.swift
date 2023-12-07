import AOCKit
import OrderedCollections

private enum Card: String, CaseIterable, Equatable {
    case ace = "A"
    case king = "K"
    case queen = "Q"
    case jack = "J"
    case ten = "T"
    case nine = "9"
    case eight = "8"
    case seven = "7"
    case six = "6"
    case five = "5"
    case four = "4"
    case three = "3"
    case two = "2"
    
    var value: Int {
        switch self {
        case .ace: 14
        case .king:
            13
        case .queen:
            12
        case .jack:
            11
        case .ten:
            10
        case .nine:
            9
        case .eight:
            8
        case .seven:
            7
        case .six:
            6
        case .five:
            5
        case .four:
            4
        case .three:
            3
        case .two:
            2
        }
    }
    
    var part2Value: Int {
        switch self {
        case .ace: 14
        case .king:
            13
        case .queen:
            12
        case .jack:
            1
        case .ten:
            10
        case .nine:
            9
        case .eight:
            8
        case .seven:
            7
        case .six:
            6
        case .five:
            5
        case .four:
            4
        case .three:
            3
        case .two:
            2
        }
    }
}

private enum Classification {
    case fiveOfAKind
    case fourOfAKind
    case fullHouse
    case threeOfAKind
    case twoPair
    case onePair
    case highCard
}

private struct P1Hand {
    let cards: [Card]
    let countedCards: [Card: Int]
    let bid: Int
    let classification: Classification
    
    init(cards: [Card], bid: Int) {
        self.cards = cards
        self.countedCards = cards.reduce(into: [Card: Int]()) { cards, card in
            cards[card, default: 0] += 1
        }
        self.bid = bid
        self.classification = Self.classify(cards: self.countedCards)
    }
    
    static func classify(cards: [Card: Int]) -> Classification {
        switch cards.count {
        case 1: return .fiveOfAKind
        case 2: // full house, four of a kind
            let sortedCards = cards.sorted(by: { $0.value > $1.value })
            if sortedCards[0].value == 4 {
                return .fourOfAKind
            } else if sortedCards[0].value == 3 {
                return .fullHouse
            } else {
                fatalError()
            }
            
        case 3: // two pair or three of a kind
            let sortedCards = cards.sorted(by: { $0.value > $1.value })
            if sortedCards[0].value == 3 {
                return .threeOfAKind
            } else if sortedCards[0].value == 2 && sortedCards[1].value == 2 {
                return .twoPair
            } else {
                fatalError()
            }
        case 4: // one pair
            return .onePair
        case 5: // high card
            return .highCard
            
        default: fatalError()
        }
    }
}

extension P1Hand: Comparable {
    fileprivate static func < (lhs: P1Hand, rhs: P1Hand) -> Bool {
        if lhs.classification == rhs.classification {
            for (lhsCard, rhsCard) in zip(lhs.cards, rhs.cards) {
                if lhsCard.value == rhsCard.value { continue }
                return lhsCard.value < rhsCard.value
            }
        }
    
        switch (lhs.classification, rhs.classification) {
        case (.fiveOfAKind, _): return false
        case (_, .fiveOfAKind): return true
        case (.fourOfAKind, _): return false
        case (_, .fourOfAKind): return true
        case (.fullHouse, _): return false
        case (_, .fullHouse): return true
        case (.threeOfAKind, _): return false
        case (_, .threeOfAKind): return true
        case (.twoPair, _): return false
        case (_, .twoPair): return true
        case (.onePair, _): return false
        case (_, .onePair): return true
        default: fatalError()
        }
    }
}

private struct P2Hand {
    let cards: [Card]
    let countedCards: [Card: Int]
    let bid: Int
    let classification: Classification
    
    init(cards: [Card], bid: Int) {
        self.cards = cards
        self.countedCards = cards.reduce(into: [Card: Int]()) { cards, card in
            cards[card, default: 0] += 1
        }
        self.bid = bid
        self.classification = Self.classify(cards: self.countedCards)
    }
    
    static func classify(cards: [Card: Int]) -> Classification {
        let jackCount = cards[.jack, default: 0]
        
        var cards = cards
            .filter { $0.key != .jack }
        
        if cards.count == 5 - jackCount {
            guard let largest = cards.max(by: { $0.key.part2Value < $1.key.part2Value }) else { return .fiveOfAKind }
            cards[largest.key, default: 0] += jackCount
        } else {
            guard let largest = cards.max(by: { lhs, rhs in
                if lhs.value == rhs.value {
                    return lhs.key.part2Value < rhs.key.part2Value
                }
                
                return lhs.value < rhs.value
            }) else { return .fiveOfAKind }
            cards[largest.key, default: 0] += jackCount
        }
        
        switch cards.count {
        case 1: return .fiveOfAKind
        case 2: // full house, four of a kind
            let sortedCards = cards.sorted(by: { $0.value > $1.value })
            if sortedCards[0].value == 4 {
                return .fourOfAKind
            } else if sortedCards[0].value == 3 {
                return .fullHouse
            } else {
                fatalError()
            }
            
        case 3: // two pair or three of a kind
            let sortedCards = cards.sorted(by: { $0.value > $1.value })
            if sortedCards[0].value == 3 {
                return .threeOfAKind
            } else if sortedCards[0].value == 2 && sortedCards[1].value == 2 {
                return .twoPair
            } else {
                fatalError()
            }
        case 4: // one pair
            return .onePair
        case 5: // high card
            return .highCard
            
        default: fatalError()
        }
    }
}

extension P2Hand: Comparable {
    fileprivate static func < (lhs: P2Hand, rhs: P2Hand) -> Bool {
        if lhs.classification == rhs.classification {
            for (lhsCard, rhsCard) in zip(lhs.cards, rhs.cards) {
                if lhsCard.part2Value == rhsCard.part2Value { continue }
                return lhsCard.part2Value < rhsCard.part2Value
            }
        }
    
        switch (lhs.classification, rhs.classification) {
        case (.fiveOfAKind, _): return false
        case (_, .fiveOfAKind): return true
        case (.fourOfAKind, _): return false
        case (_, .fourOfAKind): return true
        case (.fullHouse, _): return false
        case (_, .fullHouse): return true
        case (.threeOfAKind, _): return false
        case (_, .threeOfAKind): return true
        case (.twoPair, _): return false
        case (_, .twoPair): return true
        case (.onePair, _): return false
        case (_, .onePair): return true
        default: fatalError()
        }
    }
}

public enum Day7: Solution {
    public typealias SolutionInput = String

    public static func part1(_ input: SolutionInput) throws -> some CustomStringConvertible {
        return try handsParser
            .parse(input[...])
            .map(P1Hand.init(cards:bid:))
            .sorted { lhs, rhs in
                lhs < rhs
            }
            .enumerated()
            .map { (index, hand) in
                return hand.bid * (index + 1)
            }
            .sum()
    }
    
    public static func part2(_ input: SolutionInput) throws -> some CustomStringConvertible {
        return try handsParser
            .parse(input[...])
            .map(P2Hand.init(cards:bid:))
            .sorted { lhs, rhs in
                lhs < rhs
            }
            .enumerated()
            .map { (index, hand) in
                return hand.bid * (index + 1)
            }
            .sum()
    }
}

private let handParser = Parse(input: Substring.self) {
    Many(5) {
        Card.parser()
    }
    
    Whitespace()
    
    Int.parser()
}

private let handsParser = Parse(input: Substring.self) {
    Many {
        handParser
    } separator: {
        Whitespace(1, .vertical)
    }
}
