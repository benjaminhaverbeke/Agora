import { registerReactControllerComponents } from '@symfony/ux-react';

import './bootstrap';
import './sass/app.scss';


registerReactControllerComponents(require.context('./react/controllers', true, /\.(j|t)sx?$/));