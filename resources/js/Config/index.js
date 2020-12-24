import Repository from "./Repository";

export function config(key, defaultValue = null) {
    try {
        const repository = new Repository(require("./items.json"));

        return repository.get(key, defaultValue);
    } catch (error) {
        if (process.env.APP_ENV === "local") {
            console.log(error);
        }
    }
}
