export default abstract class Component<T extends HTMLElement = HTMLElement> {

    protected _node: JQuery<T>;

    protected abstract render(): JQuery<T>;

    public get node(): JQuery<T> {
        if (!this._node) {
            this._node = this.render();
        }

        return this._node;
    }

}

export const el = <T extends HTMLElement>(def: string, ...children: (string | JQuery)[]): JQuery<T> => {
    const [tag, cssClass] = def.split('.');

    const element = $(`<${tag}/>`) as JQuery<T>;
    cssClass && element.addClass(cssClass);
    element.append(...children);

    return element;
};