import './http';
import './portal';
import './moment';
import './inertia';
import initProgressIndicator from './progress';

try {
    initProgressIndicator();
} catch (error) {
    console.log(error);
}
