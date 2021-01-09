import Repository from '@/Config/Repository';

let config;

beforeEach(() => {
    config = new Repository({ foo: 'bar' });
});

describe('Configurations Repository Tests', () => {
    test('Instantiates the repository', () => {
        expect(config.all()).toEqual({ foo: 'bar' });
    });
});
