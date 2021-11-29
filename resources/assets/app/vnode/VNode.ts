export type Child = string | number | boolean | VNode

//TODO moduł który może zastąpić jquery

export class VNode<T extends Node = Node> {

    protected _dom: T = null;
    protected _children: VNode[] = [];
    protected _isMounted = false;

    protected _onMount?: () => void;
    protected _onUnmount?: () => void;

    public get dom() {
        return this._dom;
    }

    public get children() {
        return this._children;
    }

    public isText(): boolean {
        return this._dom instanceof Text;
    }

    public isMounted(): boolean {
        return this._isMounted;
    }

    public onMount(onMount: () => void) {
        this._onMount = onMount;

        return this;
    }

    public onUnmount(onUnmount: () => void) {
        this._onUnmount = onUnmount;

        return this;
    }

    public append(...children: Child[]) {
        children.forEach(child => {
            if (child instanceof VNode) {
                this._children.push(child);

                if (this.isMounted()) {
                    VNode.mount(child, this._dom);
                }
            } else {
                const childVnode = new VNode();
                childVnode._dom = document.createTextNode(child + '');
                this._children.push(childVnode);
            }
        });

        return this;
    }

    public static create(def: string | VNode, ...children: Child[]): VNode {
        const vnode = new VNode();

        if (typeof def === 'string') {
            const [tag, cssClass] = def.split('.');

            vnode._dom = document.createElement(tag);
            cssClass && (vnode._dom as HTMLElement).classList.add(cssClass);
        } else if (def instanceof VNode) {
            children.unshift(def);
        }

        children.forEach(child => {
            if (child instanceof VNode) {
                vnode._children.push(child);
            } else {
                const childVnode = new VNode();
                childVnode._dom = document.createTextNode(child + '');
                vnode._children.push(childVnode);
            }
        });

        return vnode;
    }

    public static mount(node: VNode, target: Node) {
        if (node._dom) {
            target.appendChild(node._dom);

            node._children.forEach(child => {
                VNode.mount(child, node._dom);
            });

            node._isMounted = true;
            node._onMount && node._onMount();
        } else {
            node._children.forEach(child => {
                VNode.mount(child, target);
            });
        }
    }

    public static unmount(node: VNode) {
        //TODO
    }

}

const vn = <T extends Node = Node>(def: string | VNode, ...children: Child[]) => {
    return VNode.create(def, ...children) as VNode<T>;
};

export default vn;