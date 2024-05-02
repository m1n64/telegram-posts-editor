import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import {Head, Link} from "@inertiajs/react";
import {LayoutContentHeader} from "@/Components/Header/LayoutContentHeader.jsx";
import {Card} from "@/Components/Card/Card.jsx";
import {Table} from "antd";
import {mdParser} from "@/Components/Markdown/TgMarkdownEditor.jsx";
import {PostBadge} from "@/Components/Badge/PostBadge.jsx";
import {DefaultLink} from "@/Components/Link/DefaultLink.jsx";


export default function HistoryTable({auth, channelId, posts}) {
    const columns = [
        {
            title: 'ID',
            dataIndex: 'id',
            key: 'id',
            sorter: (a, b) => a.id - b.id,
        },
        {
            title: 'Title',
            dataIndex: 'title',
            key: 'title',
        },
        {
            title: 'Content',
            dataIndex: 'content',
            key: 'content',
            render: (content) => <div>{truncateText(content)}</div>,
        },
        {
            title: 'Created At',
            dataIndex: 'created_at',
            key: 'created_at',
            sorter: (a, b) => new Date(a.created_at).getTime() - new Date(b.created_at).getTime(),
        },
        {
            title: 'Publish Date',
            dataIndex: 'publish_date',
            key: 'publish_date',
            sorter: (a, b) => new Date(a.created_at).getTime() - new Date(b.created_at).getTime(),
        },
        {
            title: 'Status',
            dataIndex: 'status',
            key: 'status',
            render: (status) => <PostBadge type={status.type}>{status.title}</PostBadge>,
            sorter: (a, b) => a.status.id - b.status.id,
        },
        {
            title: 'Actions',
            key: 'actions',
            render: (post) => <DefaultLink href={route('content.editor', {id: channelId, postId: post.id})}>Edit</DefaultLink>

        },
    ];

    const truncateText = (text) => {
        if (text.length > 150) {
            return text.substring(0, 150) + '...';
        } else {
            return text;
        }
    };

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
                <Table
                    dataSource={posts.data}
                    columns={columns}
                />
            </Card>
        </AuthenticatedLayout>
    )
}
