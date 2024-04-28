import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import {Head, Link} from "@inertiajs/react";
import {LayoutContentHeader} from "@/Components/Header/LayoutContentHeader.jsx";
import {Card} from "@/Components/Card/Card.jsx";
import {BoxElement, BoxList, Element, List} from "@/Components/List/index.js";
import {DefaultLink} from "@/Components/Link/DefaultLink.jsx";
import {mdParser} from "@/Components/Markdown/TgMarkdownEditor.jsx";
import {ButtonLink} from "@/Components/Link/ButtonLink.jsx";
import {PostBadge} from "@/Components/Badge/PostBadge.jsx";

export default function History({auth, channelId, posts}) {
    return (
        <AuthenticatedLayout
            user={auth.user}
            header={
                <LayoutContentHeader
                    channelId={channelId}
                    title={"History"}
                />
            }
        >
            <Head
                title="History"
            />

            <Card>
                <List>
                    {posts.data.map((post) => (
                        <Element className={"my-4"} key={post.id}>
                            <div className={"flex justify-between"}>
                                <div>
                                    <strong>{post.title}</strong> <span className={"text-gray-500"}>{post.created_at}</span>
                                </div>
                                <PostBadge type={post.status.type}>{post.status.title}</PostBadge>
                            </div>
                            {post.publish_date && <div className={"text-gray-500"}>Publish at {post.publish_date}</div>}
                            <div
                                className="p-3 border border-gray-100 rounded-lg max-h-56 relative"
                                style={{ paddingBottom: '10px' }}
                            >
                                <div
                                    className={"max-h-52 text-gray-400"}
                                    style={{ overflowY: 'hidden' }}
                                    dangerouslySetInnerHTML={{ __html: mdParser.render(post.content) }}
                                />
                                <div
                                    className="absolute inset-x-0 bottom-0 h-[100%] bg-gradient-to-t from-white"
                                    style={{ pointerEvents: 'none' }}
                                />
                            </div>
                            {/*<div className={"p-3 border border-gray-200 rounded-lg max-h-80"} dangerouslySetInnerHTML={{__html: mdParser.render(post.content)}}>
                            </div>*/}
                            <div>
                                <DefaultLink href={route('content.editor', {id: channelId, postId: post.id})}>Edit</DefaultLink>
                            </div>
                        </Element>
                    ))}
                </List>
                <BoxList>
                    {posts.meta.links.map(link => (
                        <ButtonLink
                            href={link.url}
                            type={"link"}
                            disabled={link.active}
                            dangerouslySetInnerHTML={{__html: link.label}}
                        />
                    ))}
                </BoxList>
            </Card>
        </AuthenticatedLayout>
    )
}
