export default abstract class Component {

    protected dom: JQuery;

    protected abstract render(): JQuery;

    public getDom(): JQuery {
        if (!this.dom) {
            this.dom = this.render();
        }

        return this.dom;
    }

}

export type ElementDef = string | JQuery | Component;
export type ElementChild = string | number | boolean | JQuery | Component;

export const el = (def: ElementDef, ...children: ElementChild[]): JQuery => {
    if (typeof def === 'string') {
        const [tag, cssClass] = def.split('.');

        const element = $(`<${tag}/>`);
        cssClass && element.addClass(cssClass);

        children.forEach(child => {
            if (child instanceof Component) {
                element.append(child.getDom());
            } else if (typeof child === 'object') {
                element.append(child);
            } else {
                element.append(child + '');
            }
        });

        return element;
    } else {
        let element: JQuery;

        if (def instanceof Component) {
            element = $(def.getDom());
        } else {
            element = $(def);
        }

        children.forEach(child => {
            if (child instanceof Component) {
                element = element.add(child.getDom());
            } else if (typeof child === 'object') {
                element = element.add(child);
            } else {
                element = element.add(child + '');
            }
        });

        return element;
    }
};