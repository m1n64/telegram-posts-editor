import {useState} from "react";
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import {Head, Link} from "@inertiajs/react";
import {LayoutContentHeader} from "@/Components/Header/LayoutContentHeader.jsx";
import {Card} from "@/Components/Card/Card.jsx";
import {Table} from "antd";
import {DefaultLink} from "@/Components/Link/DefaultLink.jsx";
import SecondaryButton from "@/Components/SecondaryButton.jsx";
import {API, errorToast} from "@/api/api.js";
import {toast} from "react-toastify";

export default function HistoryTable({auth, channelId, scheduledPosts}) {
    const [posts, setPosts] = useState(scheduledPosts);

    const columns = [
        /*{
            title: 'UUID',
            dataIndex: 'uuid',
            key: 'UUID',
        },*/
        {
            title: 'Post ID',
            dataIndex: 'post',
            key: 'post_id',
            render: (post) => post.id,
            sorter: (a, b) => a.post.id - b.post.id,
        },
        {
            title: 'Title',
            dataIndex: 'post',
            key: 'title',
            render: (post) => <DefaultLink href={route('content.editor', {id: channelId, postId: post.id})}>{post.title}</DefaultLink>
        },
        {
            title: 'Created At',
            dataIndex: 'post',
            key: 'created_at',
            sorter: (a, b) => new Date(a.post.created_at).getTime() - new Date(b.post.created_at).getTime(),
            render: (post) => new Date(post.created_at).toLocaleString(),
        },
        {
            title: 'Updated At',
            dataIndex: 'post',
            key: 'updated_at',
            sorter: (a, b) => new Date(a.post.updated_at).getTime() - new Date(b.post.updated_at).getTime(),
            render: (post) => new Date(post.updated_at).toLocaleString(),
        },
        {
            title: 'Publish Date',
            dataIndex: 'post',
            key: 'publish_date',
            sorter: (a, b) => new Date(a.post.created_at).getTime() - new Date(b.post.created_at).getTime(),
            render: (post) => post.publish_date
        },
        {
            title: 'Actions',
            dataIndex: 'uuid',
            key: 'actions',
            render: (uuid) => <SecondaryButton onClick={()=>removeJob(uuid)}>Remove from queue</SecondaryButton>
        }
    ];

    const removeJob = (jobUuid) => {
        API.posts.scheduleRemove(channelId, jobUuid)
            .then(response => {
                setPosts(posts.filter(post => post.uuid !== response.data.data.uuid));
            })
            .catch(err => {
                errorToast(err.response.data.message);
            });
    }

    return (
        <AuthenticatedLayout
            user={auth.user}
            header={
                <LayoutContentHeader
                    channelId={channelId}
                    title={"Scheduled"}
                />
            }
        >
            <Head
                title="Scheduled posts"
            />

            <Card>
                <Table
                    dataSource={posts}
                    columns={columns}
                />
            </Card>
        </AuthenticatedLayout>
    )
}
