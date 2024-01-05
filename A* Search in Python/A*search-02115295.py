"""
CIS 561- Aritificial Intelligence- Project 1 - Task-3
Name: Adithya Prahasith Malladi
Student ID: 02115295
Email ID:amalladi@umassd.edu
 """

import heapq
# input the number of rows for the grid eg:5 
print("Implementation of A* Search Algorithm for the Deterministic Setting of the Path finding Game")
print()

rows=int(input("enter number of rows of the grid "))
# input the number of columns for the grid eg:5
cols=int(input("enter number of columns of the grid "))

mushroom_kingdom = [['.' for _ in range(cols)] for _ in range(rows)]

# input the start position of mario eg: start_position at (0, 0) then input as 0 0
start_position = tuple(map(int, input("Input the start position of Mario as x space y ").split()))

# input the goal position of mushroom eg: if u want the goal at 4,4 then input as 4 4
goal_position = tuple(map(int, input("Input the goal position of Mushroom as x space y ").split()))
print()
# input the number of warp pipes in the grid, including entering and exiting eg:4
num_pipes=int(input("enter the number of warp pipes for entering and exiting "))
warp_pipes = {}
print("warp pipes are connected as " %warp_pipes)

# choose the locations for the warp pipes to be located in the grid
for i in range(1,num_pipes+1):
    entering_pipes = tuple(map(int, input(f"Enter the position of warp_pipe_entering {i} (x space y): ").split()))
    exit_pipes = tuple(map(int, input(f"Enter the position of warp_pipe_exiting {i} (x space y): ").split()))
    warp_pipes[entering_pipes] = exit_pipes
    mushroom_kingdom[entering_pipes[0]][entering_pipes[1]]='P'
    mushroom_kingdom[exit_pipes[0]][exit_pipes[1]]='P'
print()

# input the number of obstacles needed to be in the grid and choose the locations in the grid
obs=int(input("enter number of obstacles "))
for i in range(1,obs+1):
    obstacles = tuple(map(int, input(f"Enter the position of obstacle {i} (x space y): ").split())) # if u want obstacle at 0,3 cell then input as 0 3
    mushroom_kingdom[obstacles[0]][obstacles[1]]='O'
    
#setting the values for S and G in the grid  
mushroom_kingdom[start_position[0]][start_position[1]]='S'
mushroom_kingdom[goal_position[0]][goal_position[1]]='G'
print()
print("The mushroom kingdom grid is ")
print(mushroom_kingdom)

# defining the heuristic function-manhattan distance
def heuristic_function(x1, x2):
    return abs(x1[0] - x2[0]) + abs(x1[1] - x2[1])


def find_path(mushroom_kingdom):
    # Defining the  moves possible (up, down, left, right)
    moves = [(-1, 0), (1, 0), (0, -1), (0, 1)]
    
    # Initialize the open list with the start position
    open_list = [(0, start_position)]
    path_visited = {}
    g_values = {start_position: 0}
    
    # Initialize path as an empty list
    path = []
    
    while open_list:
        current_cost, current_pos = heapq.heappop(open_list)
        
        if current_pos == goal_position:
            # Reconstruct the path
            path = []
            while current_pos in path_visited:
                path.insert(0, current_pos)
                current_pos = path_visited[current_pos]
            path.insert(0, start_position)
            break
        
        for move in moves:
            x, y = current_pos[0] + move[0], current_pos[1] + move[1]  # Calculate the coordinates of the neighbor
            neighbor_cell = (x, y)
            
            # Check if neighbor is a warp pipe entrance
            if neighbor_cell in warp_pipes:   
                warp_exit = warp_pipes[neighbor_cell]  # Warp pipe exit
                temp_gn = g_values[current_pos]+2   # Update cost (g) when entering a warp pipe
                neighbor_cell = warp_exit
            else:
                temp_gn = g_values[current_pos]+1
            # Check if the neighbor is within the grid and not an obstacle
            if 0 <= x < len(mushroom_kingdom) and 0 <= y < len(mushroom_kingdom[0]) and mushroom_kingdom[x][y] != 'O':
                if neighbor_cell not in g_values or temp_gn < g_values[neighbor_cell]:
                    g_values[neighbor_cell] = temp_gn
                    h_value = heuristic_function(neighbor_cell, goal_position)
                    f_value = temp_gn + h_value
                    heapq.heappush(open_list, (f_value, neighbor_cell))
                    path_visited[neighbor_cell] = current_pos

    return path

# Find the optimal path
optimal_path_find = find_path(mushroom_kingdom)

if start_position==goal_position: # case for if start and goal are at same positions 
    print("S is at the goal")
elif start_position!=goal_position:
    # Calculate and print the total cost
    if num_pipes>0 or obs>0:
        total_cost = len(optimal_path_find) 
    else:
        total_cost = len(optimal_path_find)-1
    if optimal_path_find:
        print()
        print("Path of S from start to goal:")
        for step, S_position in enumerate(optimal_path_find):
                g = step  
                if S_position==start_position or step==0:
                    g=0
                elif S_position in warp_pipes:
                    g=2
                elif num_pipes==0 or obs==0:
                    g=step
                elif num_pipes>=1 or obs>=1:
                    g=step+1
                h = heuristic_function(S_position, goal_position)
                f = g + h
                print(f"Step {step}: {S_position} - Cost (g): {g} - Heuristic (h): {h} - Total Cost (f): {f}")
                print()
        print(f"Total Cost to reach the goal is: {total_cost}")
    else:
        print("No path found.")
           
       
