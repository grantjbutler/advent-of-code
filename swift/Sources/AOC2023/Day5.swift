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
        let garden = try GardenParser(seedParser: SeedParser(), transform: Garden.init).parse(input[...])
        
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
        let garden = try GardenParser(seedParser: SeedPart2Parser(), transform: GardenPart2.init).parse(input[...])
        
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

private struct SeedParser: Parser {
    var body: some Parser<Substring, [Int]> {
        Parse {
            "seeds: "
            
            IntegersParser()
        }
    }
}

private struct SeedPart2Parser: Parser {
    var body: some Parser<Substring, [ClosedRange<Int>]> {
        Parse {
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
    }
}

private struct MapParser: Parser {
    let name: String
    
    var body: some Parser<Substring, [ClosedRange<Int>: Int]> {
        Parse {
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
    }
}

private struct GardenParser<SeedParser: Parser, Output>: Parser where SeedParser.Input == Substring {
    let seedParser: SeedParser
    let transform: (SeedParser.Output, [ClosedRange<Int>: Int], [ClosedRange<Int>: Int], [ClosedRange<Int>: Int], [ClosedRange<Int>: Int], [ClosedRange<Int>: Int], [ClosedRange<Int>: Int], [ClosedRange<Int>: Int]) -> Output
    
    var body: some Parser<Substring, Output> {
        Parse(transform) {
            seedParser
            
            Whitespace(2, .vertical)
            
            MapParser(name: "seed-to-soil")
            
            Whitespace(2, .vertical)
            
            MapParser(name: "soil-to-fertilizer")
            
            Whitespace(2, .vertical)
            
            MapParser(name: "fertilizer-to-water")
            
            Whitespace(2, .vertical)
            
            MapParser(name: "water-to-light")
            
            Whitespace(2, .vertical)
            
            MapParser(name: "light-to-temperature")
            
            Whitespace(2, .vertical)
            
            MapParser(name: "temperature-to-humidity")
            
            Whitespace(2, .vertical)
            
            MapParser(name: "humidity-to-location")
        }
    }
}
