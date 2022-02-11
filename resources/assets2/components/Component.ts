import { Properties as CSS } from 'csstype';

export type MountEventHandler = (isMounted: boolean) => void;
export type EnableEventHandler = (isEnabled: boolean) => void;

export default abstract class Component {

    protected _vdom: JQuery;
    protected _isMounted = false;

    /** On element mount callback */
    protected _onMount?(): void;

    /** On element unmount callback */
    protected _onUnmount?(): void;

    protected mountEventHandlers: MountEventHandler[] = [];
    protected enableEventHandlers: EnableEventHandler[] = [];

    public constructor() {
        // TODO rozwiązanie z eventami jest raczej mało wydajne, spróbować napisać plugin do jquery
        document.addEventListener('dom:changed', () => {
            if (!this._vdom?.get(0)) {
                return;
            }

            //TODO rozwiązać przypadek gdy komponent reperezentuje np. kilka divów
            if (document.contains(this._vdom.get(0))) {
                if (!this._isMounted) {
                    this._onMount && this._onMount();
                    this._isMounted = true;

                    this.mountEventHandlers.forEach(handler => handler(true));
                }
            } else {
                if (this._isMounted) {
                    this._onUnmount && this._onUnmount();
                    this._isMounted = false;

                    this.mountEventHandlers.forEach(handler => handler(false));
                }
            }
        });
    }

    /**
     * Returns element virtual DOM.
     */
    public get vdom(): JQuery {
        return this._vdom;
    }

    public get isMounted(): boolean {
        return this._isMounted;
    }

    public onMount(handler: MountEventHandler) {
        this.mountEventHandlers.push(handler);

        return this;
    }

    public addClass(className: string) {
        this._vdom.addClass(className);
        return this;
    }

    public removeClass(className: string) {
        this._vdom.removeClass(className);
        return this;
    }

    /**
     * Set element `style` attribute.
     * @param style 
     */
    public setStyle(style: CSS) {
        this.css(style);
        return this;
    }

    /**
     * Get or set css properties(s).
     */
    public css(properties: CSS): this;
    public css<T extends keyof CSS>(property: T): CSS[T];
    public css<T extends keyof CSS>(property: T, value: CSS[T]): this;
    public css<T extends keyof CSS>(arg1: T | CSS, arg2?: CSS[T]) {
        if (typeof arg1 === 'object') {
            this._vdom.css(arg1 as any);

            return this;
        } else if (arg2) {
            this._vdom.css({ [arg1]: arg2 });
            
            return this;
        } else {
            return this._vdom.css(arg1);
        }
    }

}

export interface ILifecycleHooks {
    onMount?: () => void;
    onUnmount?: () => void;
}

export type ComponentNode = string | number | boolean | JQuery | Component;

export const getVdom = (node: ComponentNode): JQuery => {
    if (node === null || node === undefined) {
        return null;
    } else if (node instanceof Component) {
        return node.vdom;
    } else if (node instanceof $) {
        return node as JQuery;
    } else {
        return $('<span/>').append('' + node);
    }
};

export function el(expr: string, children?: ComponentNode | ComponentNode[], hooks?: ILifecycleHooks): JQuery;
export function el(elements: ComponentNode[]): JQuery;
export function el(arg1: string | ComponentNode[], arg2?: ComponentNode | ComponentNode[], arg3?: ILifecycleHooks): JQuery {
    if (Array.isArray(arg1)) {
        const elements = arg1.map(node => getVdom(node));

        return elements.reduce((prev, curr) => prev.add(curr), elements[0]);
    } else {
        const [tag, ...classNames] = arg1.split('.');
        const element = $(`<${tag}/>`);

        classNames.forEach(className => {
            element.addClass(className);
        });

        if (arg2) {
            if (!Array.isArray(arg2)) {
                arg2 = [arg2];
            }

            element.append(arg2.map(node => getVdom(node)));
        }

        if (arg3) {
            const { onMount, onUnmount } = arg3;
            let isMounted = false;

            document.addEventListener('dom:changed', () => {
                if (document.contains(element.get(0))) {
                    if (!isMounted) {
                        onMount && onMount();
                        isMounted = true;
                    }
                } else {
                    if (isMounted) {
                        onUnmount && onUnmount();
                        isMounted = false;
                    }
                }
            });
        }

        return element;
    }
}

export const isElChild = (node: any): boolean => {
    if (node instanceof $ || node instanceof Component) {
        return true;
    } else {
        const type = typeof node;

        return type === 'string' || type === 'number' || type === 'boolean';
    }
};

new MutationObserver((mutations) => {
    document.dispatchEvent(new CustomEvent('dom:changed', {
        detail: mutations
    }));
}).observe(document, { childList: true, subtree: true });