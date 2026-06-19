import './bootstrap';
import AuthService from './auth';

AuthService.initAuth().catch(() => {});
