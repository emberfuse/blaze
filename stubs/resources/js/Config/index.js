import Repository from './Repository';

export default {
    install(app, options = {}) {
        app.mixin({
            methods: {
                config(key, defaultValue) {
                    const repository = new Repository(options);

                    return repository.get(key, defaultValue);
                },
            },
        });
    },
};
