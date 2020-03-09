import React, {Component} from 'react';

class Posts extends Component {

    componentDidMount() {
        document.title = this.props.page
    }

    render() {
        const {page, data} = this.props;
        const todoItems = data.map(function (todo, k) {
            return (
                <li key={todo.id}>
                    <div className="view">
                        <input
                            className="toggle"
                            type="checkbox"
                            checked={todo.status === 'completed'}
                        />
                        <label>
                            {todo.title}
                        </label>
                        <button className="destroy"  />
                    </div>
                    <input
                        ref="editField"
                        className="edit"
                    />
                </li>
            );
        }, this);

        return (
            <div>
                {todoItems}
            </div>
        );
    }

}

export default Posts;