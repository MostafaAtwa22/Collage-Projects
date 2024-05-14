import java.util.*;

import javafx.util.Pair;

public class MetroGraphBFS {
    private Map<Integer, Station> stations; // Map of station ID to Station object
    private List<Connect> connections; // List of connections between stations
    private int nextStatId = 1;

    public MetroGraphBFS() {
        stations = new HashMap<>();
        connections = new ArrayList<>();
    }

    public boolean exists(String name) {
        for (Station station : stations.values()) {
            if (station.getName().equals(name)) {
                return true;
            }
        }
        return false;
    }

    public Station getStationByNameOrCreate(String name) {
        // Check if the station already exists
        for (Station station : stations.values()) {
            if (station.getName().equals(name)) {
                return station; // Station already exists, return it
            }
        }
        // If the station doesn't exist, create a new one and add it to the graph
        Station newStation = new Station(nextStatId++, name, new ArrayList<>());
        addStation(newStation);
        return newStation;
    }

    public void addStation(Station station) {
        stations.put(station.getId(), station);
    }

    public void addConnection(Connect connection) {
        connections.add(connection);
    }

    public Map<Integer, Station> getStations() {
        return stations;
    }

    public Pair<Long, List<Station>> shortestPath(int fromStationId, int toStationId) {
        Queue<Integer> queue = new LinkedList<>();
        boolean[] visited = new boolean[stations.size() + 1];
        int[] distance = new int[stations.size() + 1];
        int[] prev = new int[stations.size() + 1];

        Arrays.fill(visited, false);
        Arrays.fill(distance, -1);
        Arrays.fill(prev, -1);

        queue.offer(fromStationId);
        visited[fromStationId] = true;
        distance[fromStationId] = 0;

        while (!queue.isEmpty()) {
            int currentStationId = queue.poll();
            if (currentStationId == toStationId) {
                break; // Exit loop if destination is reached
            }

            for (Connect connection : connections) {
                if (connection.getFromStationId() == currentStationId && !visited[connection.getToStationId()]) {
                    queue.offer(connection.getToStationId());
                    visited[connection.getToStationId()] = true;
                    distance[connection.getToStationId()] = distance[currentStationId] + 1;
                    prev[connection.getToStationId()] = currentStationId;
                }
            }
        }

        List<Station> path = new ArrayList<>();
        int temp = toStationId;
        while (temp != -1) {
            path.add(stations.get(temp));
            temp = prev[temp];
        }
        Collections.reverse(path);

        return new Pair<>((long) distance[toStationId], path);
    }
}
