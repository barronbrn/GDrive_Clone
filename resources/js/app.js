import './bootstrap';
import Alpine from 'alpinejs';
import fileManager from './file-manager';

window.Alpine = Alpine;

Alpine.data('fileManager', fileManager);

Alpine.start();
