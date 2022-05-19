
const GET_USER_ARTICLES = `
query GetUserArticles($page: Int!){
  user(username: "jrsofty") {
    publication {
      domain
      posts(page: $page) {
        title
        cuid
        brief
        slug
        isActive
      }
    }
  }
}`;
async function gql(query, variables={}) {
    const data = await fetch('https://api.hashnode.com/', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            query,
            variables 
        })
    });

    return data.json();
};