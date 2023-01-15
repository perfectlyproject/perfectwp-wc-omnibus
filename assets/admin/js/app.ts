import 'core-js';
import {get} from 'underscore';
import axios from 'axios';
import i18next, {Resource} from 'i18next';
import Modal from './components/modal';

declare global {
    interface Window {
        wppd: {
            data: Record<string, any>;
            translations: Record<string, any>;
        };
    }
}
declare let __webpack_public_path__: string;

export default class App {
    static instance: App;

    static init(): void {
        if (!App.instance) {
            App.instance = new App();
        }
    }

    constructor() {
        __webpack_public_path__ = App.getData('publicPath');

        App.loadTranslations();
        App.setAxiosDefaults();
        App.loadModules();
    }

    public static getData(key: string): any {
        return get(window.wppd.data, key);
    }

    private static setAxiosDefaults(): void {
        axios.defaults.headers.common = {
            'Content-Type': 'multipart/form-data',
        };
        axios.defaults.baseURL = App.getData('ajaxUrl');
    }

    private static loadModules(): void {
        [Modal].forEach((module) => {
            module.factory();
        });
    }

    private static loadTranslations(): void {
        const translationResources: Resource = {};
        translationResources[App.getData('lang')] = {
            translation: window.wppd.translations
        };

        i18next.init({
            lng: App.getData('lang'),
            initImmediate: false,
            resources: translationResources
        });
    }
}

App.init();