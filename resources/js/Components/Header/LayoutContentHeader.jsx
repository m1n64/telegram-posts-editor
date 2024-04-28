import {Link} from "@inertiajs/react";

export const LayoutContentHeader = ({channelId, title = "Content Editor", showNewPost = false}) => {
    return (
        <div className={"flex"}>
            <h2 className="font-semibold text-xl text-gray-800 leading-tight">{title}</h2>
            <Link href={route('content.editor', {id: channelId})} hidden={showNewPost} className="text-xl leading-tight text-blue-400 hover:text-blue-200 px-2">New</Link>
            <Link href={route('content.history', {id: channelId})} className="text-xl leading-tight text-blue-400 hover:text-blue-200 px-2">All posts</Link>
        </div>
    );
}
