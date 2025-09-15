import './bootstrap';

import Alpine from 'alpinejs';
import { createIcons, icons } from 'lucide';


document.addEventListener('DOMContentLoaded', () => {
  // turn all <i data-lucide="icon-name"> into inline SVGs
  createIcons({ icons });
});

window.Alpine = Alpine;

Alpine.start();
