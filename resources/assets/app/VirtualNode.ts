import { 
    classModule, 
    eventListenersModule, 
    init, 
    propsModule, 
    styleModule, 
    VNode
} from 'snabbdom';

export const patch = init([
    classModule,
    propsModule,
    styleModule,
    eventListenersModule,
]);

export default class VirtualNode {

    protected _vnode: VNode;

    public mount() {
        
    }

    public unmount() {

    }

}