import {createRoot} from 'react-dom/client';
import SelectComponent from './components/SelectComponent.jsx';
import ruleFormOptions from './configs/ruleFormOptions.jsx';

const app = document.getElementById('app');
if (app) {
    const root = createRoot(app);
    root.render(<SelectComponent {...ruleFormOptions.modelTypeSelect} />);
}
