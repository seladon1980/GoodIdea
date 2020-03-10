import React, {Component} from 'react';
import {destroy} from "../redux/actions";
import {connect} from "react-redux";

class Posts extends Component {

    componentDidMount() {
        document.title = this.props.page
    }

    destroy(event, id, data) {
        this.props.dispatch(destroy(event, id, data));
    }

    render() {
        const {blogData} = this.props;
        const todoItems = blogData.data.map(function (todo, k) {
            return (
                <li key={todo.id}>
                    <div className="view">
                        <input
                            className="toggle"
                            type="checkbox"
                            checked={todo.status === 'completed'}
                        />
                        <label>
                            {todo.title} ({todo.like})
                        </label>
                        <button className="destroy"  onClick={event => this.destroy(event, todo.id, blogData)}/>
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

function mapStateToProps(state) {
    const {page} = state;
    return page;
}

export default connect(mapStateToProps)(Posts);