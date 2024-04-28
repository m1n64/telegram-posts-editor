export const PrimaryInputButton = ({className = '', disabled, children, ...props}) => {
    return (
        <input
            className={
                `inline-flex items-center px-4 py-2 bg-blue-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 ${
                    disabled && 'opacity-25'
                } ` + className
            }
            {...props}
            value={children}
        />
    );
}

export const PrimaryInputFileButton = ({className = '', disabled, children, ...props}) => {
    return (
        <label className="inline-flex items-center px-4 py-2 bg-blue-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
            {children}
            <input
                type="file"
                className="hidden"
                {...props}
            />
        </label>
    );
}
