import AOCKit

struct Garden {
    let seeds: [Int]
    let seedToSoil: [ClosedRange<Int>: Int]
    let soilToFertilizer: [ClosedRange<Int>: Int]
    let fertilizerToWater: [ClosedRange<Int>: Int]
    let waterToLight: [ClosedRange<Int>: Int]
    let lightToTemperature: [ClosedRange<Int>: Int]
    let temperatureToHumidity: [ClosedRange<Int>: Int]
    let humidityToLocation: [ClosedRange<Int>: Int]
}

struct GardenPart2 {
    let seeds: [ClosedRange<Int>]
    let seedToSoil: [ClosedRange<Int>: Int]
    let soilToFertilizer: [ClosedRange<Int>: Int]
    let fertilizerToWater: [ClosedRange<Int>: Int]
    let waterToLight: [ClosedRange<Int>: Int]
    let lightToTemperature: [ClosedRange<Int>: Int]
    let temperatureToHumidity: [ClosedRange<Int>: Int]
    let humidityToLocation: [ClosedRange<Int>: Int]
}

public enum Day5: Solution {
    public typealias SolutionInput = String

    public static func part1(_ input: SolutionInput) throws -> some CustomStringConvertible {
        let garden = try part1Parser.parse(input[...])
        
        return garden
            .seeds
            .map { seed in
                return [garden.seedToSoil, garden.soilToFertilizer, garden.fertilizerToWater, garden.waterToLight, garden.lightToTemperature, garden.temperatureToHumidity, garden.humidityToLocation].reduce(seed) { partialResult, mapping in
                    guard let range = mapping.keys.first(where: { $0.contains(partialResult) }) else { return partialResult }
                    
                    return partialResult - range.lowerBound + mapping[range]!
                }
            }
            .min()!
    }
    
    public static func part2(_ input: SolutionInput) throws -> some CustomStringConvertible {
        let garden = try part2Parser.parse(input[...])
        
        return garden
            .seeds
            .flatMap { range in
                range.map { seed in
                    return [garden.seedToSoil, garden.soilToFertilizer, garden.fertilizerToWater, garden.waterToLight, garden.lightToTemperature, garden.temperatureToHumidity, garden.humidityToLocation].reduce(seed) { partialResult, mapping in
                        guard let range = mapping.keys.first(where: { $0.contains(partialResult) }) else { return partialResult }
                        
                        return partialResult - range.lowerBound + mapping[range]!
                    }
                }
            }
            .min()!
    }
}

private let seedParser = Parse(input: Substring.self) {
    "seeds: "
    
    Many {
        Int.parser()
    } separator: {
        Whitespace()
    }
}

private let seedPart2Parser = Parse(input: Substring.self) {
    "seeds: "
    
    Many {
        Int.parser()
        Whitespace()
        Int.parser()
    } separator: {
        Whitespace()
    }
    .map { pairs in
        return pairs.map { lowerBound, length in
            return ClosedRange(uncheckedBounds: (lower: lowerBound, upper: lowerBound + length - 1))
        }
    }
}

private func makeMapParser(named name: String) -> AnyParser<Substring, [ClosedRange<Int>: Int]> {
    return Parse(input: Substring.self) {
        "\(name) map:"
        Whitespace(1, .vertical)
        
        Many {
            Int.parser()
            
            Whitespace()
            
            Int.parser()
            
            Whitespace()
            
            Int.parser()
        } separator: {
            Whitespace(1, .vertical)
        }
        .map { maps in
            return maps.reduce(into: [ClosedRange<Int>: Int]()) { partialResult, triple in
                partialResult[.init(uncheckedBounds: (lower: triple.1, upper: triple.1 + triple.2 - 1))] = triple.0
            }
        }
    }
    .eraseToAnyParser()
}

private let part1Parser = Parse(input: Substring.self, Garden.init(seeds:seedToSoil:soilToFertilizer:fertilizerToWater:waterToLight:lightToTemperature:temperatureToHumidity:humidityToLocation:)) {
    seedParser
    
    Whitespace(2, .vertical)
    
    makeMapParser(named: "seed-to-soil")
    
    Whitespace(2, .vertical)
    
    makeMapParser(named: "soil-to-fertilizer")
    
    Whitespace(2, .vertical)
    
    makeMapParser(named: "fertilizer-to-water")
    
    Whitespace(2, .vertical)
    
    makeMapParser(named: "water-to-light")
    
    Whitespace(2, .vertical)
    
    makeMapParser(named: "light-to-temperature")
    
    Whitespace(2, .vertical)
    
    makeMapParser(named: "temperature-to-humidity")
    
    Whitespace(2, .vertical)
    
    makeMapParser(named: "humidity-to-location")
}

private let part2Parser = Parse(input: Substring.self, GardenPart2.init(seeds:seedToSoil:soilToFertilizer:fertilizerToWater:waterToLight:lightToTemperature:temperatureToHumidity:humidityToLocation:)) {
    seedPart2Parser
    
    Whitespace(2, .vertical)
    
    makeMapParser(named: "seed-to-soil")
    
    Whitespace(2, .vertical)
    
    makeMapParser(named: "soil-to-fertilizer")
    
    Whitespace(2, .vertical)
    
    makeMapParser(named: "fertilizer-to-water")
    
    Whitespace(2, .vertical)
    
    makeMapParser(named: "water-to-light")
    
    Whitespace(2, .vertical)
    
    makeMapParser(named: "light-to-temperature")
    
    Whitespace(2, .vertical)
    
    makeMapParser(named: "temperature-to-humidity")
    
    Whitespace(2, .vertical)
    
    makeMapParser(named: "humidity-to-location")
}
