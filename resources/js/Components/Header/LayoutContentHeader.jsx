import {Link, router} from "@inertiajs/react";

export const LayoutContentHeader = ({channelId, title = "Content Editor", showNewPost = false}) => {
    const className = "text-xl leading-tight text-blue-400 hover:text-blue-200 px-2 ";
    const activeClassName = "text-xl leading-tight text-blue-400 px-2 font-bold cursor-default";

    const routes = [
        {
            link: route('content.editor', {id: channelId}),
            title: "New",
            useHidden: true,
        },
        {
            link: route('content.history', {id: channelId}),
            title: "All posts",
            useHidden: false,
        },
        {
            link: route('content.scheduled', {id: channelId}),
            title: "Scheduled posts",
            useHidden: false,
        }
    ];

    return (
        <div className={"flex"}>
            <h2 className="font-semibold text-xl text-gray-800 leading-tight">{title}</h2>
            {routes.map((route) => (
                <Link href={route.link} hidden={route.useHidden && showNewPost} className={window.location.href === route.link ? activeClassName: className}>{route.title}</Link>
            ))}
        </div>
    );
}
