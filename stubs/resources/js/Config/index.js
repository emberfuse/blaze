import Repository from './Repository';

/**
 * Get the specified configuration value.
 *
 * @param   {String}  key
 * @param   {Any}  defaultValue
 *
 * @return  {Any}
 */
export default function config(key, defaultValue = null) {
    try {
        const repository = new Repository(require('./items.json'));

        return repository.get(key, defaultValue);
    } catch (error) {
        if (process.env.MIX_APP_ENV === 'local') {
            console.log(error);
        }
    }
}
