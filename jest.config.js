module.exports = {
    moduleNameMapper: {
        '^@/(.*)$': '<rootDir>/resources/js/$1',
        '^~/(.*)$': '<rootDir>/resources/js/$1',
    },
    moduleFileExtensions: ['js', 'vue', 'json'],
    transform: {
        '^.+\\.js$': 'babel-jest',
        '^.+\\.jsx?$': 'babel-jest',
        '.*\\.(vue)$': 'vue-jest',
    },
    collectCoverage: true,
    collectCoverageFrom: [
        '<rootDir>/resources/js/Components/**/*.vue',
        '<rootDir>/resources/js/Pages/**/*.vue',
    ],
};
