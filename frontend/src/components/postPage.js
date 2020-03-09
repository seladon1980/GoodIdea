import React, {Component} from 'react';
import {connect} from 'react-redux'
import {fetchCardIfNeeded} from '../redux/actions'

import Posts from './posts'

class PostPage extends Component {

    componentWillMount() {
        this.props.dispatch(fetchCardIfNeeded())
    }

    render() {
        const {isFetching, blogData} = this.props;

        return (
            <div>
                {isFetching && <h2>Загрузка...</h2>}
                {blogData &&
                <section className="main">
                    <input
                        id="toggle-all"
                        className="toggle-all"
                        type="checkbox"
                        onChange={this.toggleAll}
                    />
                    <label
                        htmlFor="toggle-all"
                    />
                    <ul className="todo-list">
                        <Posts {...blogData}/>
                    </ul>
                </section>
                }
            </div>
        );
    }
}

function mapStateToProps(state) {
    const {page} = state;
    return page;
}

export default connect(mapStateToProps)(PostPage);