import App from './app/App';
import scrollbar from './layout/Content/Scrollbar/Scrollbar';

export const app = new App();
$('#app').append(app.getDom());

scrollbar.updateThumb();