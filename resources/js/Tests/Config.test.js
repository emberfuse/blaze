import Repository from '@/Config/Repository';

describe('Configurations Repository Tests', () => {
    test('it can be instantiated', () => {
        let config = new Repository({});

        expect(config).toBeInstanceOf(Repository);
    });

    test('it can get all config items from repository', () => {
        let config = new Repository({ foo: 'bar' });

        expect(config.all()).toEqual({ foo: 'bar' });
    });

    test('it can get value of specific key', () => {
        let config = new Repository({ foo: 'bar' });

        expect(config.get('foo')).toEqual('bar');
    });

    test('it will return default value if given specific key was not found', () => {
        let config = new Repository({ foo: 'bar' });

        expect(config.get('fuz', 'baz')).toEqual('baz');
    });
});
