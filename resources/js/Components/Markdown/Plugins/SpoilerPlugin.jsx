import {PluginComponent} from "react-markdown-editor-lite";
import {FontAwesomeIcon} from "@fortawesome/react-fontawesome";

export default class SpoilerPlugin extends PluginComponent {
    static pluginName = 'spoiler';

    constructor(props) {
        super(props);
    }

    render() {
        return (
            <button onClick={()=>this.editor.insertMarkdown('spoiler')} className={"hover:text-black"}>
                <FontAwesomeIcon icon="fa-regular fa-square-minus" />
            </button>
        );
    }
}
