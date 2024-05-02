export const PostBadge = ({children, type = 'draft', ...props}) => {
    let color = '';

    switch (type) {
        case 'draft':
            color = 'bg-gray-100 text-gray-800';
            break;
        case 'ready_to_publish':
            color = 'bg-blue-100 text-blue-800';
            break;
        case 'pending':
            color = 'bg-yellow-100 text-yellow-800';
            break;
        case 'published':
            color = 'bg-green-100 text-green-800';
            break;
        case 'error':
            color = 'bg-red-100 text-red-800';
            break;
        case 'canceled':
            color = 'bg-orange-100 text-orange-800';
            break;
    }

    return (
        <div
            className={`inline-flex items-center mx-2 px-2.5 py-0.5 rounded-full font-medium ${color}`}
            {...props}
        >
            {children}
        </div>
    );
}
