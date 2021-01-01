import './http';
import './portal';
import './moment';
import './inertia';
import initProgressIndicator from './progress';

try {
    initProgressIndicator();
} catch (error) {
    if (process.env.MIX_APP_ENV) {
        console.log(error);
    }
}
