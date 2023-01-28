import {get} from 'underscore';
import Variations from "./components/variations";

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

        __webpack_public_path__ = App.getData('publicPath');

        App.loadModules();
    }

    public static getData(key: string): any {
        return get(window.wppd.data, key);
    }

    private static loadModules(): void {
        [Variations].forEach((module) => {
            module.factory();
        });
    }
}

App.init();