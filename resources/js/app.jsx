import {createRoot} from 'react-dom/client';
import SelectComponent from './components/SelectComponent.jsx';
import workflowFormOptions from './configs/workflowFormOptions.jsx';

const app = document.getElementById('app');
if (app) {
    const root = createRoot(app);
    root.render(<SelectComponent {...workflowFormOptions.modelTypeSelect} />);
}
