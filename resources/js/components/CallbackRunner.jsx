const CallbackRunner = ({parent, callback, name, value}) => {
    if (callback){
        callback({parent: {...parent}}, name, value);
    }
    return <></>;
}

export default CallbackRunner;
